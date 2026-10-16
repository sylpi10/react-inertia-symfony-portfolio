// @vitest-environment jsdom
import { router } from "@inertiajs/react";
import userEvent from "@testing-library/user-event";
import Review from "./Review";
import { cleanup, render, screen } from "@testing-library/react";
import { afterEach, describe, expect, it, vi } from "vitest";

// pas de serveur en test : on fournit une page Inertia vide (aucun message flash)
vi.mock("@inertiajs/react", async (importOriginal) => ({
    ...(await importOriginal<typeof import("@inertiajs/react")>()),
    usePage: () => ({ flash: {} }),
}));

// après chaque test : on retire le formulaire affiché et on remet le vrai router
afterEach(() => {
    cleanup();
    vi.restoreAllMocks();
});

// affiche le formulaire avec deux projets ; send() clique sur Envoyer
// et renvoie les données qui seraient parties au serveur
function renderForm() {
    const visit = vi.spyOn(router, "visit").mockImplementation(() => {});
    const user = userEvent.setup();

    render(
        <Review
            projects={[
                { id: 3, name: "Maha" },
                { id: 7, name: "Ava" },
            ]}
        />,
    );

    const send = async () => {
        await user.click(screen.getByRole("button", { name: "Envoyer" }));
        return visit.mock.calls[0]?.[1]?.data;
    };

    return { user, send };
}

describe("Review", () => {
    it("envoie l'avis rempli par le client", async () => {
        const { user, send } = renderForm();

        // le client remplit le formulaire
        await user.type(screen.getByLabelText("Votre nom"), "Marie D.");
        await user.click(screen.getByLabelText("Ava"));
        await user.type(
            screen.getByLabelText("Votre avis"),
            "Super travail, livré dans les temps.",
        );
        await user.click(screen.getByLabelText(/J’accepte/));
        await user.click(screen.getByRole("button", { name: "Envoyer" }));

        // la requête serait partie avec ces données
        expect(await send()).toEqual({
            author: "Marie D.",
            authorRole: "",
            text: "Super travail, livré dans les temps.",
            projects: [7],
            consent: true,
            website: "",
        });
    });

    it("n'envoie pas un projet coché puis décoché", async () => {
        const { user, send } = renderForm();
        // le client toggle un projet
        await user.click(screen.getByLabelText("Ava"));
        await user.click(screen.getByLabelText("Ava"));
        expect(await send()).toMatchObject({ projects: [] });
    });
});
