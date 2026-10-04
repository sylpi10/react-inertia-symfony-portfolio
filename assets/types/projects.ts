export type ProjectProps = {
    id: number;
    slug: string;
    name: string;
    date: string;
    technos: string;
    weblink: string | null;
    background: string;
    githublink: string | null;
};

export type ProjectDetailsProps = Omit<ProjectProps, "background"> & {
    description: string | null;
    detailPic: string;
    detail_pic_mobile: string | null;
};

export type ProjectLink = Pick<ProjectProps, "slug" | "name" | "background"> & {
    teaser: string;
};
