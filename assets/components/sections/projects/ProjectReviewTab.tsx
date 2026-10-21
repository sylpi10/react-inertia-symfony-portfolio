import { formatDay } from "../../../lib/dates";
import { ProjectDetailsProps } from "../../../types/projects";
import CalendarIcon from "../../ui/CalendarIcon";
import JobIcon from "../../ui/JobIcon";
import PersonIcon from "../../ui/PersonIcon";
import GoogleReview from "../reviews/GoogleReview";
import ReviewBlockquote from "../reviews/ReviewBlockquote";

export default function ProjectReviewTab({
    project,
}: {
    project: ProjectDetailsProps;
}) {
    return (
        <>
            <h2>L'avis du client</h2>
            {project.reviews.map((review) => (
                <div className="review-details" key={review.id}>
                    <div className="review-content">
                        <GoogleReview review={review} />
                        <ReviewBlockquote review={review} />
                    </div>
                    <p className="review-author">
                        <span className="author-name">
                            <PersonIcon />
                            {review.author}
                        </span>
                        {review.authorRole && (
                            <span className="author-role">
                                <JobIcon />
                                {review.authorRole}
                            </span>
                        )}
                        <time dateTime={review.postedAt}>
                            <CalendarIcon />
                            {formatDay(review.postedAt)}
                        </time>
                    </p>
                </div>
            ))}
        </>
    );
}
