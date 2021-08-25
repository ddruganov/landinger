import Api from "@/common/api";
import Landing from "@/types/landing/Landing";
import { Getters, Mutations, Actions, Module } from "vuex-smart-module";

// State
class LandingState {
  landings: Landing[] = [];
}

// Getters
class LandingGetters extends Getters<LandingState> {
  get landings() {
    return this.state.landings;
  }
  landingById(id: number) {
    return this.state.landings.find(l => l.id === id);
  }
}

// Actions
export const LOAD_ALL_LANDINGS = 'loadAllLandings';
export const CREATE_LANDING = 'createLanding';
class LandingActions extends Actions<LandingState, LandingGetters, LandingMutations, LandingActions> {
  [LOAD_ALL_LANDINGS](): void {
    Api.landing
      .all()
      .then((response) => {
        if (!response.success) {
          throw new Error(response.error);
        }

        this.commit(SET_ALL_LANDINGS, response.data);
      })
      .catch((e) => e);
  }

  [CREATE_LANDING](): void {
    Api.landing.create()
      .then((response) => {
        if (!response.success) {
          throw new Error(response.error);
        }

        this.commit(ADD_LANDING, response.data);
      })
      .catch((e) => e);
  }
}

// Mutations
export const SET_ALL_LANDINGS = "setAllLandings";
export const ADD_LANDING = "addLanding";
class LandingMutations extends Mutations<LandingState> {
  [SET_ALL_LANDINGS](payload: Landing[]): void {
    this.state.landings = payload;
  }
  [ADD_LANDING](payload: Landing): void {
    this.state.landings.push(payload);
  }
}

// Create a module with module asset classes
export const landingStore = new Module({
  namespaced: true,
  state: LandingState,
  getters: LandingGetters,
  actions: LandingActions,
  mutations: LandingMutations,
});
