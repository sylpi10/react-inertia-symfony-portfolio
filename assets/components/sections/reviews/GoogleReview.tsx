import { ReviewProps } from "../../../types/reviews";

export default function GoogleReview({ review }: { review: ReviewProps }) {
    return (
        <>
            {review.googleRating && (
                <p className="review-google">
                    <span className="stars" aria-hidden="true">
                        {"★".repeat(review.googleRating)}
                        <span className="empty">
                            {"★".repeat(5 - review.googleRating)}
                        </span>
                    </span>
                    <span className="visually-hidden">
                        Note Google : {review.googleRating} sur 5.
                    </span>{" "}
                    {review.googleReviewUrl ? (
                        <a
                            href={review.googleReviewUrl}
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Avis Google
                        </a>
                    ) : (
                        "Avis Google"
                    )}
                </p>
            )}
        </>
    );
}
