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
                <p className="review-meta">
                    {review.projects.map((project, i) => (
                        <span key={project.slug}>
                            {i > 0 && ", "}
                            <Link href={projectPath(project.slug, audience)}>
                                {project.name}
                            </Link>
                        </span>
                    ))}
                    {" · "}
                    <time dateTime={review.postedAt}>
                        {formatDay(review.postedAt)}
                    </time>
                </p>
            </footer>
        </li>
    );
}
