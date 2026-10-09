import { ProjectProps } from "./projects";

export type ReviewProps = {
    id: number;
    author: string;
    authorRole: string;
    postedAt: string;
    text: string;
    projects: Pick<ProjectProps, "name" | "slug">[];
};
