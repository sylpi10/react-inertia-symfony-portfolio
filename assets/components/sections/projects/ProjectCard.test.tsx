// @vitest-environment jsdom
import { afterEach, describe, expect, it } from "vitest";
import { cleanup, render, screen } from "@testing-library/react";
import ProjectCard from "./ProjectCard";
import { ProjectProps } from "../../../types/projects";

afterEach(cleanup);

const project: ProjectProps = {
    id: 1,
    slug: "directicimes",
    name: "Directicimes",
    date: "2025",
    technos: "React,Symfony",
    weblink: "https://example.com",
    background: "directicimes.webp",
    thumbnail: null,
    githublink: null,
    miniDescription: null,
    auditMade: null,
    lastAuditDate: null,
    pagespeedCapture: null,
    perfText: null,
    reviews: [],
};

// texte des liens : écrit dans le HTML (Google), pas seulement en aria-label
describe("ProjectCard", () => {
    it("donne un texte descriptif au lien vers les détails", () => {
        render(<ProjectCard project={project} audience="client" />);

        const link = screen.getByRole("link", {
            name: "Voir le projet Directicimes",
        });
        expect(link.getAttribute("href")).toBe(
            "/creation-site-web/projets/directicimes",
        );
        expect(link.hasAttribute("aria-label")).toBe(false);
    });

    it("nomme le site du projet dans le texte du lien externe", () => {
        render(<ProjectCard project={project} audience="client" />);

        const link = screen.getByRole("link", {
            name: "Site de Directicimes (nouvel onglet)",
        });
        expect(link.textContent).toContain("Site de Directicimes");
    });
});
