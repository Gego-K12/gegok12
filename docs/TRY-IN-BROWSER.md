# Try your academy in GitHub Codespaces

This is a temporary development preview, not permanent hosting.
It uses your existing GitHub account. GitHub may require billing verification;
included compute/storage quotas vary and overages can cost money.
The configuration has been checked structurally, but not built or run end to end.

1. Open https://github.com/codespaces/new?repo=aymanmagdy12/gegok12&ref=try/codespaces
2. Select a 4 GB or larger machine if available and create the Codespace.
3. Wait for PHP 8.4, Node 24, Composer, and MySQL to start.
4. In the editor open the Ports tab. Open port 8000 in your browser.
   Keep its visibility Private. Append /installer/ to its URL.
5. Complete the visual installer using these DEVELOPMENT ONLY database values:
   - Host: db
   - Port: 3306
   - Database: academy_trial
   - Username: academy
   - Password: trial_only_password
6. Enter your academy name and create your own administrator login. Use the
   full forwarded HTTPS URL as APP_URL if requested.
7. Let the installation finish, then open the login page. The installer runs
   fresh migrations and includes demo data. Only use this isolated trial DB.
8. If assets fail to build, open the terminal and run npm install followed by
   npm run production. Report the actual error if dependency resolution fails;
   do not assume the application has passed testing.
9. Edit files in the browser editor, then commit and push changes using Source
   Control. Do not commit .env, passwords, uploads, or real student information.

## Troubleshooting
If generated links go to localhost or HTTP, set APP_URL in .env to your
forwarded HTTPS URL and run php artisan config:clear.
If login or a page fails, read storage/logs and share the error without secrets.
Starting this preview cannot guarantee that all upstream application features work.

## Stop and data
Stop the Codespace from https://github.com/codespaces when finished to stop
compute usage. Storage can still count toward quota while stopped.
The site is unavailable while stopped. Restart the same Codespace to continue.
Database files live in a Docker volume within this Codespace. Export anything
needed before rebuilding or deleting it; deletion can remove all trial data.
Never use this configuration or its published database passwords for production.
