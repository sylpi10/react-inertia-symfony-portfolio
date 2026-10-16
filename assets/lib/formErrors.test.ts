import { describe, expect, it } from "vitest";
import { listError } from "./formErrors";

describe("listError", () => {
    it("renvoie l'erreur posée sur le champ lui-même", () => {
        const errors = { projects: "Liste invalide" };

        expect(listError(errors, "projects")).toBe("Liste invalide");
    });
});
