import { useState } from "react";
import { truncate } from "../../../lib/truncate";
import { ReviewProps } from "../../../types/reviews";

export default function ReviewBlockquote({
    review,
    limit,
}: {
    review: ReviewProps;
    // sans limit : texte entier, pas de « Lire la suite »
    limit?: number;
}) {
    const [expanded, setExpanded] = useState(false);
    const isLong = limit !== undefined && review.text.length > limit;

    return (
        <blockquote>
            <p>
                {isLong && !expanded
                    ? truncate(review.text, limit)
                    : review.text}

                {isLong && (
                    <>
                        <br />
                        <button
                            type="button"
                            className="read-more"
                            aria-expanded={expanded}
                            onClick={() => setExpanded(!expanded)}
                        >
                            {expanded ? "Réduire" : "Lire la suite"}
                        </button>
                    </>
                )}
            </p>
        </blockquote>
    );
}
