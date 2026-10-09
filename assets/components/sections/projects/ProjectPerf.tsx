import { formatDay } from "../../../lib/dates";
import { projectImageUrl } from "../../../lib/images";
import { ProjectPerfProps } from "../../../types/projects";

export default function ProjectPerf({
    project,
}: {
    project: Pick<
        ProjectPerfProps,
        "auditMade" | "lastAuditDate" | "pagespeedCapture" | "perfText"
    >;
}) {
    return (
        <div className="project-audit-wrapper">
            {project.perfText && (
                <div className="description">
                    <div
                        dangerouslySetInnerHTML={{ __html: project.perfText }}
                    />
                </div>
            )}
            {project.pagespeedCapture && (
                <figure>
                    <img
                        src={projectImageUrl(project.pagespeedCapture)}
                        width={600}
                        height={300}
                        alt="Scores PageSpeed Insights du site"
                    />
                    {project.lastAuditDate && (
                        <figcaption>
                            Audit du{" "}
                            <time dateTime={project.lastAuditDate}>
                                {formatDay(project.lastAuditDate)}
                            </time>
                        </figcaption>
                    )}
                </figure>
            )}
        </div>
    );
}
