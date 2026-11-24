// @vitest-environment jsdom
import { afterEach, describe, expect, it, vi } from "vitest";
import { cleanup, render, screen } from "@testing-library/react";

// Contact et Footer ont leurs propres tests et lisent les props Inertia : remplacés ici
vi.mock("../components/sections/contact/Contact", () => ({
    default: () => null,
}));
vi.mock("../components/sections/Footer", () => ({ default: () => null }));

import PrixSiteVitrine from "./PrixSiteVitrine";

afterEach(cleanup);

describe("PrixSiteVitrine", () => {
    it("affiche l'année reçue du serveur dans le h1", () => {
        render(<PrixSiteVitrine year={2027} />);

        expect(screen.getByRole("heading", { level: 1 }).textContent).toBe(
            "Prix d’un site vitrine en 2027",
        );
    });

    it("affiche les tarifs de la section Tarifs", () => {
        render(<PrixSiteVitrine year={2026} />);

        expect(screen.getByText("à partir de 1 200 €")).toBeTruthy();
        expect(screen.getByText("à partir de 2 200 €")).toBeTruthy();
    });
});
