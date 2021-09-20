import Api from "@/common/api";
import appInstance from "@/main";
import Landing from "@/types/landing/Landing";
import LandingBackgroundType from "@/types/landing/LandingBackgroundType";
import LandingLink from "@/types/landing/LandingLink";
import { Getters, Mutations, Actions, Module } from "vuex-smart-module";

type LandingCommon = {
  background: {
    types: LandingBackgroundType[]
  }
};

// State
class LandingState {
  landings: Landing[] = [];
  common: LandingCommon = {
    background: {
      types: []
    }
  };
}

// Getters
class LandingGetters extends Getters<LandingState> {
  get common() {
    return this.state.common;
  }
  get backgroundIds() {
    return this.state.common.background.types.map(type => type.id);
  }
  backgroundById(id: number) {
    return this.state.common.background.types.find(type => type.id === id);
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
export const CREATE_LANDING_LINK = 'createLandingLink';
export const DELETE_LANDING_LINK = 'deleteLandingLink';
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

  [CREATE_LANDING_LINK](landingId: number): void {
    Api.landing.link
      .create(landingId)
      .then((response) => {
        if (response.exception) {
          throw new Error(response.exception);
        }

        this.commit(ADD_LINK, { landingId: landingId, link: response.data });
      })
      .catch((e) => appInstance.$notifications.error(e.message, "Ошибка создания ссылки"))
  }

  [DELETE_LANDING_LINK]({ landingId, id }: { landingId: number; id: number }): Promise<boolean> {
    return new Promise((resolve) => {
      Api.landing.link.delete(landingId, id)
        .then((response) => {
          if (response.exception) {
            throw new Error(response.exception);
          }

          this.commit(DELETE_LANDING_LINK, { landingId: landingId, id: id });
          appInstance.$notifications.success("Ссылка успешно удалена")
          resolve(true);
        })
        .catch((e) => {
          resolve(false);
          appInstance.$notifications.error(e.message, "Ошибка удаления ссылки")
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

        appInstance.$notifications.success("Сохранено");
      })
      .catch((e) => appInstance.$notifications.error(e.message, "Ошибка сохранения лендинга"))
  }
}

// Mutations
export const SET_COMMON = "setCommon";
export const SET_ALL_LANDINGS = "setAllLandings";
export const ADD_LANDING = "addLanding";
export const ADD_LINK = "addLink";
class LandingMutations extends Mutations<LandingState> {
  [SET_COMMON](payload: any): void {
    this.state.common = payload;
  }

  [SET_ALL_LANDINGS](payload: Landing[]): void {
    this.state.landings = payload;
  }

  [ADD_LANDING](payload: Landing): void {
    this.state.landings.push(payload);
  }

  [DELETE_LANDING](payload: number): void {
    this.state.landings = this.state.landings.filter(landing => landing.id !== payload);
  }

  [DELETE_LANDING_LINK]({ landingId, id }: { landingId: number; id: number }): void {
    const landing = this.state.landings.find(landing => landing.id === landingId)!;
    landing.links = landing.links.filter(link => link.id !== id);
  }

  [ADD_LINK](payload: { landingId: number, link: LandingLink }): void {
    const landing = this.state.landings.find(l => l.id === payload.landingId);
    if (!landing) {
      throw new Error(`Landing #${payload.landingId} not found`);
    }

    landing.links.push(payload.link);
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
