import Api from "@/common/api";
import appInstance from "@/main";
import Landing from "@/types/landing/Landing";
import LandingEntity from "@/types/landing/LandingEntity";
import { Getters, Mutations, Actions, Module } from "vuex-smart-module";

type LandingCommon = {
};

// State
class LandingState {
  landings: Landing[] = [];
  common: LandingCommon = {
  };
}

// Getters
class LandingGetters extends Getters<LandingState> {
  get common() {
    return this.state.common;
  }
  get landings() {
    return this.state.landings;
  }
  landingById(id: number) {
    return this.state.landings.find(l => l.id === id);
  }
}

// Actions
export const LOAD_COMMON = 'loadCommon';
export const LOAD_ALL_LANDINGS = 'loadAllLandings';
export const CREATE_LANDING = 'createLanding';
export const DELETE_LANDING = 'deleteLanding';
export const CREATE_LANDING_ENTITY = 'createLandingEntity';
export const DELETE_LANDING_ENTITY = 'deleteLandingEntity';
export const SAVE_LANDING = 'saveLanding';
class LandingActions extends Actions<LandingState, LandingGetters, LandingMutations, LandingActions> {
  [LOAD_COMMON](): void {
    Api.landing
      .common()
      .then((response) => {
        if (response.exception) {
          throw new Error(response.exception);
        }

        this.commit(SET_COMMON, response.data);
      })
      .catch((e) => e);
  }

  [LOAD_ALL_LANDINGS](): void {
    Api.landing
      .all()
      .then((response) => {
        if (response.exception) {
          throw new Error(response.exception);
        }

        this.commit(SET_ALL_LANDINGS, response.data);
      })
      .catch((e) => e);
  }

  [CREATE_LANDING](): Promise<number | undefined> {
    return new Promise((resolve) => {
      Api.landing.create()
        .then((response) => {
          if (response.exception) {
            throw new Error(response.exception);
          }

          this.commit(ADD_LANDING, response.data);
          resolve(response.data.id);
        })
        .catch((e) => {
          resolve(undefined);
          appInstance.$notifications.error(e.message, "Ошибка создания лендинга")
        });
    });
  }

  [DELETE_LANDING](id: number): Promise<boolean> {
    return new Promise((resolve) => {
      Api.landing.delete(id)
        .then((response) => {
          if (response.exception) {
            throw new Error(response.exception);
          }

          this.commit(DELETE_LANDING, id);
          appInstance.$notifications.success("Лендинг успешно удалён")
          resolve(true);
        })
        .catch((e) => {
          resolve(false);
          appInstance.$notifications.error(e.message, "Ошибка удаления лендинга")
        });
    });
  }

  [CREATE_LANDING_ENTITY]({ landingId, modelTypeId, parentId }: { landingId: number, modelTypeId: number, parentId: number | undefined }): void {
    Api.landing.entity
      .create(landingId, modelTypeId, parentId)
      .then((response) => {
        if (response.exception) {
          throw new Error(response.exception);
        }

        this.commit(ADD_ENTITY, { landingId: landingId, entity: response.data });
      })
      .catch((e) => appInstance.$notifications.error(e.message, "Ошибка создания блока"))
  }

  [DELETE_LANDING_ENTITY]({ landingId, id }: { landingId: number; id: number }): Promise<boolean> {
    return new Promise((resolve) => {
      Api.landing.entity.delete(landingId, id)
        .then((response) => {
          if (response.exception) {
            throw new Error(response.exception);
          }

          this.commit(DELETE_LANDING_ENTITY, { landingId: landingId, id: id });
          appInstance.$notifications.success("Блок успешно удалён")
          resolve(true);
        })
        .catch((e) => {
          resolve(false);
          appInstance.$notifications.error(e.message, "Ошибка удаления блока")
        });
    });
  }

  [SAVE_LANDING](landing: Landing): void {
    Api.landing
      .save(landing)
      .then((response) => {
        if (response.exception) {
          throw new Error(response.exception);
        }

        if (!response.success) {
          const errors = response.errors;
          throw new Error(errors[Object.keys(errors)[0]]);
        }

        appInstance.$notifications.success("Сохранено");
      })
      .catch((e) => appInstance.$notifications.error(e.message, "Ошибка сохранения лендинга"))
  }
}

// Mutations
export const SET_COMMON = "setCommon";
export const SET_ALL_LANDINGS = "setAllLandings";
export const ADD_LANDING = "addLanding";
export const ADD_ENTITY = "addLink";
class LandingMutations extends Mutations<LandingState> {
  [SET_COMMON](payload: any): void {
    this.state.common = payload;
  }

  [SET_ALL_LANDINGS](payload: Landing[]): void {
    this.state.landings = payload;
  }

  [ADD_LANDING](payload: Landing): void {
    this.state.landings.unshift(payload);
  }

  [DELETE_LANDING](payload: number): void {
    this.state.landings = this.state.landings.filter(landing => landing.id !== payload);
  }

  [DELETE_LANDING_ENTITY]({ landingId, id }: { landingId: number; id: number }): void {
    const landing = this.state.landings.find(landing => landing.id === landingId)!;
    landing.entities = landing.entities.filter(entity => entity.id !== id);
  }

  [ADD_ENTITY](payload: { landingId: number, entity: LandingEntity }): void {
    const landing = this.state.landings.find(l => l.id === payload.landingId);
    if (!landing) {
      throw new Error(`Лендинг #${payload.landingId} не найден`);
    }

    landing.entities.push(payload.entity);
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
