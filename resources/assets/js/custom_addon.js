import { registerWorkpermission } from './gworkpermission'
import { registerShortTermCourses } from './gshort-term-courses'
import { registerFees } from './gfees'

// later you can import:

export default function registerCustomAddon(app) {

    registerShortTermCourses(app)

    registerWorkpermission(app)

    registerFees(app)

}
