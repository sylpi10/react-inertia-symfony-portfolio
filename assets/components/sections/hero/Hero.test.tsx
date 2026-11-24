// @vitest-environment jsdom
import { describe, expect, it } from "vitest";
import { renderToString } from "react-dom/server";
import Hero from "./Hero";

// rendu serveur (ce que lit Google) : le curseur de la machine à écrire est
// affiché avant le titre, il ne doit pas apparaître dans le texte du h1
describe("Hero", () => {
    it("n'a pas de « | » dans le texte du h1 au rendu serveur", () => {
        const html = renderToString(
            <Hero
                audience="client"
                text={"Création de sites web\nà Toulouse"}
                intro="intro"
                cta="Me contacter"
                details="détails"
            />,
        );
        document.body.innerHTML = html;

        const h1 = document.querySelector("h1")!;
        expect(h1.textContent).toBe("Création de sites webà Toulouse");
    });
});
