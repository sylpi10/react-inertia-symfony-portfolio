// @vitest-environment jsdom
import { cleanup, render, screen } from "@testing-library/react";
import { afterEach, describe, expect, it, vi } from "vitest";
import type { ReviewProps } from "../../../types/reviews";
import Reviews from "./Reviews";

// jsdom n'est pas un vrai navigateur : pas de media queries ni de défilement
vi.mock("../../../hooks/useMediaQuery", () => ({ default: () => true }));
Element.prototype.scrollTo = () => {};

afterEach(cleanup);

// affiche le slider avec `count` avis identiques (seuls le nombre compte ici)
function renderReviews(count: number) {
    const reviews: ReviewProps[] = Array.from({ length: count }, (_, i) => ({
        id: i + 1,
        author: `Client ${i + 1}`,
        authorRole: null,
        postedAt: "2026-10-17T10:00:00+02:00",
        text: "Très bon travail.",
        googleRating: null,
        googleReviewUrl: null,
        projects: [],
    }));

    return render(<Reviews reviews={reviews} audience="client" />);
}

describe("Reviews", () => {
    it("n'affiche pas le total avec 1 seul avis", () => {
        renderReviews(1);

        expect(screen.queryByText("1 avis")).toBeNull();
    });

    it("n'affiche pas le total avec 2 avis", () => {
        renderReviews(2);

        expect(screen.queryByText("2 avis")).toBeNull();
    });

    it("affiche le total avec 3 avis", () => {
        renderReviews(3);

        expect(screen.queryByText("3 avis")).not.toBeNull();
    });
});
