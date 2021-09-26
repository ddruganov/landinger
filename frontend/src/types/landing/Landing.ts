import LandingBackground from "./LandingBackground";
import LandingEntity from "./LandingEntity";

type Landing = {
    id: number;
    name: string;
    alias: string;
    background: LandingBackground;
    entities: LandingEntity[];
};
export default Landing;