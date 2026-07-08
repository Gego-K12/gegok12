import { registerWorkpermission } from './gworkpermission'
import { registerShortTermCourses } from './gshort-term-courses'

// later you can import:

export default function registerCustomAddon(app) {

    registerShortTermCourses(app)

    registerWorkpermission(app)


}
