import { describe, expect, it } from "vitest";
import { truncate } from "./truncate";

describe("truncate", () => {
    it("laisse un texte court intact", () => {
        expect(truncate("Super travail", 200)).toBe("Super travail");
    });

    it("laisse intact un texte pile à la limite", () => {
        // "Super travail" = 13 caractères
        expect(truncate("Super travail", 13)).toBe("Super travail");
    });

    it("coupe un texte qui dépasse d'un caractère", () => {
        expect(truncate("Super travail!", 13)).toBe("Super…");
    });

    it("ne coupe jamais au milieu d'un mot", () => {
        // "Site livré…" ferait 11 caractères : trop long, on recule au mot d'avant
        expect(truncate("Site livré dans les temps", 10)).toBe("Site…");
    });
});
