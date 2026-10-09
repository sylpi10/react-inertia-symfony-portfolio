export type ProjectProps = {
    id: number;
    slug: string;
    name: string;
    date: string;
    technos: string;
    weblink: string | null;
    background: string;
    // vignette générée depuis la capture (1200×630), null tant qu'elle n'existe pas
    thumbnail: string | null;
    githublink: string | null;
    // texte court de la carte, déjà choisi selon le mode par le serveur
    miniDescription: string | null;
    // perfs infos
    auditMade: boolean | null;
    lastAuditDate: string | null;
    pagespeedCapture: string | null;
    perfText: string | null;
};

export type ProjectDetailsProps = Omit<
    ProjectProps,
    "background" | "thumbnail"
> & {
    description: string | null;
    detailPic: string;
    detail_pic_mobile: string | null;
};

export type ProjectPreviewProps = Pick<
    ProjectDetailsProps,
    "detailPic" | "detail_pic_mobile" | "name"
>;

export type ProjectPerfProps = Pick<
    ProjectDetailsProps,
    "auditMade" | "lastAuditDate" | "pagespeedCapture" | "perfText"
>;

export type ProjectLink = Pick<ProjectProps, "slug" | "name" | "background"> & {
    teaser: string;
};
