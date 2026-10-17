import { describe, expect, it } from "vitest";
import averageGoogleRating from "./averageGoogleRating";
import { ReviewProps } from "../types/reviews";

// avis complet, seule la note Google varie
function review(googleRating: number | null): ReviewProps {
    return {
        id: 1,
        author: "Client",
        authorRole: null,
        postedAt: "2026-10-17T10:00:00+02:00",
        text: "Très bon travail.",
        googleRating,
        googleReviewUrl: null,
        projects: [],
    };
}

describe("averageGoogleRating", () => {
    it("fait la moyenne des notes Google", () => {
        expect(averageGoogleRating([review(5), review(3)])).toBe(4);
    });

    it("ignore les avis sans note Google", () => {
        expect(averageGoogleRating([review(5), review(null), review(3)])).toBe(
            4,
        );
    });

    it("arrondit à la demi-note près", () => {
        // 4,33 → 4,5
        expect(averageGoogleRating([review(5), review(4), review(4)])).toBe(
            4.5,
        );
    });

    it("renvoie null quand aucun avis n'a de note Google", () => {
        expect(averageGoogleRating([review(null)])).toBeNull();
    });

    it("arrondi au dessus pour un .25", () => {
        // 4.25 => 4.5
        expect(
            averageGoogleRating([review(4), review(4), review(5), review(4)]),
        ).toBe(4.5);
    });

    it("arrondit vers le bas sous le .25", () => {
        // 4,2 → 4
        expect(
            averageGoogleRating([
                review(5),
                review(4),
                review(4),
                review(4),
                review(4),
            ]),
        ).toBe(4);
    });
});
