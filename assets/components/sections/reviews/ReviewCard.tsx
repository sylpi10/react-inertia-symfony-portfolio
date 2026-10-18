import { Link } from "@inertiajs/react";
import { ReviewProps } from "../../../types/reviews";
import { projectPath } from "../../../lib/paths";
import { Audience } from "../../../types/audience";
import { formatDay } from "../../../lib/dates";
import { truncate } from "../../../lib/truncate";
import { useState } from "react";

const TEXT_MAX_LENGTH = 200;

export default function ReviewCard({
    audience,
    review,
}: {
    audience: Audience;
    review: ReviewProps;
}) {
    const [expanded, setExpanded] = useState(false);
    const isLong = review.text.length > TEXT_MAX_LENGTH;
    const truncatedText: string = truncate(review.text, 200);
    const isTruncated: boolean =
        truncatedText.split("").length < review.text.split("").length;
    return (
        <li className="review-card" key={review.id}>
            {/* avis aussi déposé sur Google : sa note, et le lien vers l'original */}
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

            <blockquote>
                <p>
                    {expanded
                        ? review.text
                        : truncate(review.text, TEXT_MAX_LENGTH)}

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
            <footer>
                <div className="footer-review">
                    <p className="review-author">
                        <span className="author-name">{review.author}</span>
                        {" - "}{" "}
                        {review.authorRole && (
                            <span className="author-role">
                                {review.authorRole}
                            </span>
                        )}
                    </p>

                    <p className="review-meta">
                        {review.projects.map((project, i) => (
                            <span key={project.slug}>
                                {i > 0 && ", "}
                                <Link
                                    href={projectPath(project.slug, audience)}
                                >
                                    {project.name}
                                </Link>
                            </span>
                        ))}
                        {review.projects.length > 0 && " · "}
                        <time dateTime={review.postedAt}>
                            {formatDay(review.postedAt)}
                        </time>
                    </p>
                </div>
            </footer>
        </li>
    );
}
