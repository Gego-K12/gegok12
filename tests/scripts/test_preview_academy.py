"""Exercise the updater with a real local Git remote and stub PHP/Composer."""
import os
from pathlib import Path
import subprocess
import tempfile
import unittest


SCRIPT = Path(__file__).resolve().parents[2] / "scripts/preview-academy.sh"


class PreviewUpdaterTest(unittest.TestCase):
    def test_preserves_local_files_and_never_applies_an_old_stash_on_a_clean_tree(self):
        with tempfile.TemporaryDirectory(prefix="academy-updater-") as tmp:
            root = Path(tmp)
            remote, seed, work, binaries = [root / x for x in ("remote.git", "seed", "work", "bin")]
            seed.mkdir()
            binaries.mkdir()
            env = os.environ.copy()
            env.update({"GIT_AUTHOR_NAME": "Test", "GIT_AUTHOR_EMAIL": "test@example.test",
                        "GIT_COMMITTER_NAME": "Test", "GIT_COMMITTER_EMAIL": "test@example.test"})

            def git(cwd, *args):
                return subprocess.run(["git", *args], cwd=cwd, env=env, text=True,
                                      capture_output=True, check=True).stdout.strip()

            git(root, "init", "--bare", str(remote))
            git(seed, "init")
            for path, content in {"artisan": "test fixture", ".gitignore": ".env\n",
                                  "provider.php": "original provider\n", "public/app.css": "original CSS\n"}.items():
                target = seed / path
                target.parent.mkdir(parents=True, exist_ok=True)
                target.write_text(content)
            git(seed, "add", ".")
            git(seed, "commit", "-m", "baseline")
            git(seed, "branch", "-M", "main")
            git(seed, "remote", "add", "origin", str(remote))
            git(seed, "push", "origin", "main")
            git(root, "clone", "--branch", "main", str(remote), str(work))
            git(seed, "switch", "-c", "feature/academy-teacher-workspace")
            (seed / "journey.php").write_text("new academy code\n")
            git(seed, "add", ".")
            git(seed, "commit", "-m", "academy workflow")
            git(seed, "push", "origin", "feature/academy-teacher-workspace")

            (work / ".env").write_text("APP_NAME=ExistingAcademy\n")
            (work / "provider.php").write_text("local preview URL fix\n")
            (work / "public/app.css").write_text("locally compiled CSS\n")
            (work / "local-backup.txt").write_text("keep untracked files\n")
            command_log = root / "commands.log"
            for executable in ("php", "composer"):
                target = binaries / executable
                target.write_text('#!/bin/sh\nprintf "%s %s\\n" "'+executable+'" "$*" >> "$ACADEMY_COMMAND_LOG"\n')
                target.chmod(0o755)
            env["PATH"] = str(binaries) + os.pathsep + env["PATH"]
            env["ACADEMY_COMMAND_LOG"] = str(command_log)

            def update():
                subprocess.run(["bash", str(SCRIPT)], cwd=work, env=env,
                               text=True, capture_output=True, check=True)

            update()
            self.assertEqual("academy-connected", git(work, "branch", "--show-current"))
            self.assertEqual("new academy code\n", (work / "journey.php").read_text())
            self.assertEqual("local preview URL fix\n", (work / "provider.php").read_text())
            self.assertEqual("locally compiled CSS\n", (work / "public/app.css").read_text())
            self.assertEqual("keep untracked files\n", (work / "local-backup.txt").read_text())
            self.assertEqual("APP_NAME=ExistingAcademy\n", (work / ".env").read_text())
            self.assertTrue(git(work, "stash", "list"))
            # Commit the retained local edits; old stash still exists, but must not be reapplied.
            git(work, "add", ".")
            git(work, "commit", "-m", "preserve local runtime changes")
            update()
            self.assertEqual("", git(work, "status", "--porcelain"))
            commands = command_log.read_text()
            self.assertEqual(2, commands.count("php artisan migrate --force"))
            self.assertNotIn("migrate:fresh", commands)
            self.assertNotIn("db:seed", commands)


if __name__ == "__main__":
    unittest.main()
