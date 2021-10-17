type LandingEntity = {
    id: number;
    name: string;
    value: string;
    children: LandingEntity[];
};
export default LandingEntity;