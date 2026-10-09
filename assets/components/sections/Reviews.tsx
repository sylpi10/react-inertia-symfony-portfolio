import { ReviewProps } from "../../types/reviews";

export default function Reviews({ reviews }: { reviews: ReviewProps[] }) {
    return (
        <div className="section-container reviews-container">
            <div className="reviews-wrapper">
                <div className="title">
                    <h2 className={"section-title"}>avis de collaborateurs</h2>
                </div>

                <div className="reviews">
                    {reviews.map((r) => {
                        return (
                            <>
                                <h3>{r.author}</h3>
                                <h3>{r.authorRole}</h3>
                                <p>{r.text}</p>
                                <p>{r.projects[0].name}</p>
                            </>
                        );
                    })}
                </div>
            </div>
        </div>
    );
}
