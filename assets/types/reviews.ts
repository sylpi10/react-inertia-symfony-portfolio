import { ProjectProps } from "./projects";

export type ReviewProps = {
    id: number;
    author: string;
    // facultatif
    authorRole: string | null;
    postedAt: string;
    text: string;
    // même avis déposé sur Google : note de 1 à 5 et lien vers l'original
    googleRating: number | null;
    googleReviewUrl: string | null;
    projects: Pick<ProjectProps, "name" | "slug">[];
};
