import LandingLink from "./LandingLink";

type Landing = {
    id: number;
    name: string;
    alias: string;
    background: string;
    backgroundTypeId: number;
    links: LandingLink[];
};
export default Landing;