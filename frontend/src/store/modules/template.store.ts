import { Getters, Mutations, Actions, Module } from "vuex-smart-module";

// State
class TemplateState {}

// Getters
class TemplateGetters extends Getters<TemplateState> {}

// Actions
class TemplateActions extends Actions<TemplateState, TemplateGetters, TemplateMutations, TemplateActions> {}

// Mutations
class TemplateMutations extends Mutations<TemplateState> {}

// Create a module with module asset classes
export default new Module({
  namespaced: true,
  state: TemplateState,
  getters: TemplateGetters,
  actions: TemplateActions,
  mutations: TemplateMutations,
});
