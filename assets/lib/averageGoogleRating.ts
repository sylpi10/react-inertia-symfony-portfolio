import { ReviewProps } from "../types/reviews";

export default function averageGoogleRating(reviews: ReviewProps[]) {
    const reviewsWithGoogleRating = reviews.filter(
        (rev) => rev.googleRating !== null,
    );

    if (reviewsWithGoogleRating.length === 0) return null;

    const total = reviewsWithGoogleRating.reduce(
        (sum, rev) => sum + (rev.googleRating ?? 0),
        0,
    );

    const googleAverageRating = total / reviewsWithGoogleRating.length;

    // à la demi-note près : 4,33 → 4,5 ; 4,2 → 4
    const rounded = Math.round(googleAverageRating * 2) / 2;

    return rounded;
}
