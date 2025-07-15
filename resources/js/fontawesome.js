import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import * as solidIcons from '@fortawesome/free-solid-svg-icons'
import * as regularIcons from '@fortawesome/free-regular-svg-icons'
import * as brandIcons from '@fortawesome/free-brands-svg-icons'

// Filter and add only icon objects (those with 'iconName' property)
const addIcons = (icons) => {
  const iconList = Object.values(icons).filter(icon => icon && icon.iconName)
  library.add(...iconList)
}

addIcons(solidIcons)
addIcons(regularIcons)
addIcons(brandIcons)

export { FontAwesomeIcon }
