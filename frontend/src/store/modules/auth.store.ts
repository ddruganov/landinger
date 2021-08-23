import Api from "@/common/api";
import User from "@/types/auth/User";
import UserOrganization from "@/types/auth/userOrganization/UserOrganization";
import { Getters, Mutations, Actions, Module } from "vuex-smart-module";

// State
class AuthState {
  user: User = {};
  isVerified: boolean = false;
  organizations: UserOrganization[] = [];
  isAuthenticated: boolean = false;
}

// Getters
class AuthGetters extends Getters<AuthState> {
  get isAuthenticated() {
    return this.state.isAuthenticated;
  }
  get isVerified() {
    return this.state.isAuthenticated;
  }
  get authenticatedUser() {
    return this.state.user;
  }
  get userOrganizations() {
    return this.state.organizations;
  }
  get currentOrganization() {
    return this.state.organizations.find((o) => o.isDefault);
  }
  get workHours() {
    return [...Array(24).keys()].filter((h) => h >= 9 && h < 22);
  }
}

// Actions
export const GET_CURRENT_USER = "getCurrentUser";
export const SET_AUTHENTICATED = "setAuthenticated";
export const SET_VERIFIED = "setVerified";
class AuthActions extends Actions<AuthState, AuthGetters, AuthMutations, AuthActions> {
  [GET_CURRENT_USER]() {
    return Api.auth
      .getCurrentUser()
      .then((response) => {
        this.commit(SET_USER, response.data.user);
        this.commit(SET_ORGANIZATIONS, response.data.organizations);
        return response;
      })
      .catch((e) => e);
  }
  [SET_AUTHENTICATED](value: boolean) {
    return this.commit(SET_AUTHENTICATED, value);
  }
  [SET_VERIFIED](value: boolean) {
    return this.commit(SET_VERIFIED, value);
  }
}

// Mutations
export const SET_USER = "setUser";
export const SET_ORGANIZATIONS = "setOrganizations";
class AuthMutations extends Mutations<AuthState> {
  [SET_USER](payload: User) {
    this.state.user = payload;
  }
  [SET_ORGANIZATIONS](payload: UserOrganization[]) {
    this.state.organizations = payload;
  }
  [SET_VERIFIED](payload: boolean) {
    this.state.isVerified = payload;
  }
  [SET_AUTHENTICATED](payload: boolean) {
    this.state.isAuthenticated = payload;
  }
}

// Create a module with module asset classes
export const authStore = new Module({
  namespaced: true,
  state: AuthState,
  getters: AuthGetters,
  actions: AuthActions,
  mutations: AuthMutations,
});
