import { Link } from "@inertiajs/react";
import { ReviewProps } from "../../../types/reviews";
import { projectPath } from "../../../lib/paths";
import { Audience } from "../../../types/audience";
import { formatDay } from "../../../lib/dates";
import ReviewBlockquote from "./ReviewBlockquote";
import GoogleReview from "./GoogleReview";

export default function ReviewCard({
    audience,
    review,
}: {
    audience: Audience;
    review: ReviewProps;
}) {
    return (
        <li className="review-card" key={review.id}>
            {/* avis aussi déposé sur Google : sa note, et le lien vers l'original */}
            <GoogleReview review={review} />
            <ReviewBlockquote review={review} limit={200} />

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
