import { Ability, AbilityBuilder } from '@casl/ability'

export const defineAbilities = (userData, userAbilities) => {
  const { can, rules } = new AbilityBuilder(Ability)

  // Define rules based on user roles and abilities
  if (userData.includes('admin')) {
   
    can('manage', 'all')
  } else {
    userAbilities.forEach(ability => {
      can('read', ability) // Assuming 'read' action is required for all abilities
      // ... Define other actions based on user abilities
    })
  }

  // Grant read ability for public content to non-authenticated users
  if (userData.length === 0) {
    can('read', 'public')
  }

  return new Ability(rules)
}

// Retrieve user roles and abilities from localStorage
const userData = JSON.parse(localStorage.getItem('userData') || '[]')
const userAbilities = JSON.parse(localStorage.getItem('userAbilities') || '[]')

// Create ability based on user roles and abilities
export default defineAbilities(userData, userAbilities)
