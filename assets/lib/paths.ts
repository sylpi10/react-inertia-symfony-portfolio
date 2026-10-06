import { Audience } from "../types/audience";

// page détail d'un projet dans le mode courant (routes projects_details / client_projects_details)
export function projectPath(slug: string, audience: Audience): string {
    return audience === "client"
        ? `/creation-site-web/projets/${slug}`
        : `/projets/${slug}`;
}
