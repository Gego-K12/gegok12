import { registerAlumni } from './galumni'
import { registerCertificate } from './gcertificate'
import { registerChat } from './gchat'
import { registerWorkpermission } from './gworkpermission'
import { registerShortTermCourses } from './gshort-term-courses'
import { registerFees } from './gfees'

// later you can import:

export default function registerCustomAddon(app) {

    registerAlumni(app)

    registerCertificate(app)

    registerChat(app)

    registerShortTermCourses(app)

    registerWorkpermission(app)

    registerFees(app)

}
