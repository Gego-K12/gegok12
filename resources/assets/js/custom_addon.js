import { registerAlumni } from './galumni'
import { registerCertificate } from './gcertificate'
import { registerChat } from './gchat'
import { registerQuiz } from './gquiz'
import { registerVideoroom } from './gvideoroom'
import { registerInventory } from './ginventory'
import { registerWorkpermission } from './gworkpermission'
import { registerShortTermCourses } from './gshort-term-courses'
import { registerFees } from './gfees'

// later you can import:

export default function registerCustomAddon(app) {

    registerAlumni(app)

    registerCertificate(app)

    registerChat(app)

    registerQuiz(app)

    registerVideoroom(app)

    registerInventory(app)

    registerShortTermCourses(app)

    registerWorkpermission(app)

    registerFees(app)

}
