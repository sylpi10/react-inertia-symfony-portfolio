import { ProjectProps } from "./projects";

export type OfferProps = {
    id: number;
    title: string;
    description: string;
    projects: Pick<ProjectProps, "name" | "slug">[];
};
