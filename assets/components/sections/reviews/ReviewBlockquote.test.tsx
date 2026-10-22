// @vitest-environment jsdom
import { afterEach, describe, expect, it } from "vitest";
import ReviewBlockquote from "./ReviewBlockquote";
import { cleanup, render, screen } from "@testing-library/react";
import { ReviewProps } from "../../../types/reviews";

afterEach(cleanup);

// avis au texte de 200 caractères : plus long que la limite de 120
function renderBlockquote(limit?: number) {
    const review: ReviewProps = {
        id: 1,
        author: "Client",
        authorRole: null,
        postedAt: "2026-10-17T10:00:00+02:00",
        text: "a ".repeat(100),
        googleRating: null,
        googleReviewUrl: null,
        projects: [],
    };

    return render(<ReviewBlockquote review={review} limit={limit} />);
}

describe("ReviewBlockquote", () => {
    it("propose « Lire la suite » avec une limite", () => {
        renderBlockquote(120);

        expect(screen.getByRole("button").textContent).toBe("Lire la suite");
    });

    it("n'a pas de bouton sans limite", () => {
        renderBlockquote();

        expect(screen.queryByRole("button")).toBeNull();
    });
});
