import { projectImageUrl } from "../../../lib/images";
import { ProjectPreviewProps } from "../../../types/projects";

export default function ProjectPreview({
    project,
}: {
    project: Pick<
        ProjectPreviewProps,
        "detailPic" | "detail_pic_mobile" | "name"
    >;
}) {
    return (
        <div className="project-preview">
            <div className="preview-infos">
                <h2>Aperçu</h2>
                <p>Représentation du rendu sur desktop et mobile.</p>
            </div>
            <div className="preview-images-wrapper">
                <div className="computer-images-wrapper">
                    <div className="computer-container">
                        <div className="computer-img-container">
                            <img
                                src={projectImageUrl(project.detailPic)}
                                className="project-image"
                                alt={`Aperçu du site ${project.name} sur ordinateur`}
                                width="800"
                                height="1000"
                                decoding="async"
                            />
                        </div>
                    </div>
                </div>
                <div className="mobile-images-wrapper">
                    <div className="mobile-container">
                        <div className="mobile-img-container">
                            <img
                                src={projectImageUrl(
                                    project.detail_pic_mobile ??
                                        project.detailPic,
                                )}
                                className="project-image"
                                alt={`Aperçu du site ${project.name} sur mobile`}
                                width="300"
                                height="600"
                                loading="lazy"
                                decoding="async"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
