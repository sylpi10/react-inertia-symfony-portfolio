import { Link } from "@inertiajs/react";
import { ReviewProps } from "../../../types/reviews";
import { projectPath } from "../../../lib/paths";
import { Audience } from "../../../types/audience";
import { formatDay } from "../../../lib/dates";

export default function ReviewCard({
    audience,
    review,
}: {
    audience: Audience;
    review: ReviewProps;
}) {
    return (
        <li className="review-card" key={review.id}>
            <blockquote>
                <p>{review.text}</p>
            </blockquote>
            <footer>
                <p className="review-author">{review.author}</p>
                {review.authorRole && (
                    <p className="review-role">{review.authorRole}</p>
                )}
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
                <p className="review-meta">
                    {review.projects.map((project, i) => (
                        <span key={project.slug}>
                            {i > 0 && ", "}
                            <Link href={projectPath(project.slug, audience)}>
                                {project.name}
                            </Link>
                        </span>
                    ))}
                    {review.projects.length > 0 && " · "}
                    <time dateTime={review.postedAt}>
                        {formatDay(review.postedAt)}
                    </time>
                </p>
            </footer>
        </li>
    );
}
