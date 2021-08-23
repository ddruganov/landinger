import { Getters, Mutations, Actions, Module } from "vuex-smart-module";

// State
class LandingState {
}

// Getters
class LandingGetters extends Getters<LandingState> {
}

// Actions
class LandingActions extends Actions<LandingState, LandingGetters, LandingMutations, LandingActions> {

}

// Mutations
class LandingMutations extends Mutations<LandingState> {

}

// Create a module with module asset classes
export const authStore = new Module({
  namespaced: true,
  state: LandingState,
  getters: LandingGetters,
  actions: LandingActions,
  mutations: LandingMutations,
});
