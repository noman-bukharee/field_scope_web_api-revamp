/**
  Project and its media count
  */

SELECT COUNT(pm.project_id), p.id
FROM `project` p
         INNER JOIN project_media pm ON pm.project_id = p.id
WHERE p.company_id = 2
GROUP BY p.id
ORDER BY `COUNT(pm.project_id)` DESC


-- <editor-fold desc="Delete Project's Data via company_id">
DELETE
FROM project_media_tag
WHERE target_type = 'media'
  AND target_id IN (
    SELECT pm.id
    FROM project
             INNER JOIN project_media pm on project.id = pm.project_id
    where company_id = 38
);

DELETE
FROM project_media
WHERE project_id IN (SELECT id FROM project where company_id = 38);


DELETE
FROM project_query
WHERE project_id IN (SELECT id FROM project where company_id = 38);
DELETE
FROM reports
WHERE project_id IN (SELECT id FROM project where company_id = 38);

DELETE
FROM project_share_media
WHERE project_share_id IN (SELECT project_shares.id FROM project_shares where company_id = 38);
DELETE
FROM project_shares
WHERE company_id = 38;

DELETE
FROM hover_jobs
WHERE company_id = 38;
-- </editor-fold>

SELECT pm.project_id,
       pm.category_id,
       c.type        as cat_type,
       c.name           cat_name,
       pmt.target_id AS media_id,
       pmt.qty,
       t.id          AS tag_id,
       t.name        AS t_name,
       t.price
FROM `project_media_tag` pmt
         INNER JOIN tag t on t.id = pmt.tag_id
         INNER JOIN project_media pm ON pm.id = pmt.target_id
         INNER JOIN category c ON pm.category_id = c.id
where t.company_id = 2
  and t.deleted_at is null
  AND pm.project_id = 230
ORDER BY `pmt`.`target_id` DESC

-- <editor-fold desc="Tags attached to a Project">
SELECT pmt.*, t.name
FROM `project` p
         INNER JOIN project_media pm ON pm.project_id = p.id
         INNER JOIN project_media_tag pmt on pm.id = pmt.target_id
         INNER JOIN tag t on pmt.tag_id = t.id
WHERE pmt.deleted_at is null
  and p.company_id = 2
  and p.id = 128
-- </editor-fold>

-- <editor-fold desc="Find projects with most attached tags">
SELECT COUNT(pmt.tag_id) tag_count,p.id
FROM `project` p
         INNER JOIN project_media pm ON pm.project_id = p.id
         INNER JOIN project_media_tag pmt on pm.id = pmt.target_id
         INNER JOIN tag t on pmt.tag_id = t.id
WHERE pmt.deleted_at is null
  and p.company_id = 2
  and p.deleted_at is null
GROUP BY p.id
ORDER BY `tag_count` DESC;
-- </editor-fold>

