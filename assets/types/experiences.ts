import { ProjectProps } from "./projects";

export type ExperienceProps = {
    id: number;
    title: string;
    organization: string;
    period: string;
    description: string | null;
    technos: string | null;
    projects: Pick<ProjectProps, "name" | "slug">[];
};
