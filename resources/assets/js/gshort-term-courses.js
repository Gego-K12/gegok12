import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

// Exposed globally so the plain Alpine x-init hook in the course-manager
// Livewire view (wire:ignore'd, so Livewire never touches its DOM) can
// call ClassicEditor.create(...) without a module import of its own.
window.ClassicEditor = ClassicEditor;

export function registerShortTermCourses(app) {
    // Register this plugin's Vue components here, e.g.:
    // app.component('short-term-courses-example', () => import('./components/Example.vue').then(m => m.default));
}
