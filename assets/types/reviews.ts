import { ProjectProps } from "./projects";

export type ReviewProps = {
    id: number;
    author: string;
    // facultatif
    authorRole: string | null;
    postedAt: string;
    text: string;
    projects: Pick<ProjectProps, "name" | "slug">[];
};
