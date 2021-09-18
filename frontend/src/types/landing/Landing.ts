import LandingLink from "./LandingLink";

type Landing = {
    id: number;
    name: string;
    alias: string;
    backgroundId: number;
    links: LandingLink[];
};
export default Landing;