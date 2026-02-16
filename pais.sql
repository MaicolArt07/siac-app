CREATE VIEW v_persona 
AS 
SELECT persona.id, persona.id_pais,persona.nombre, persona.apellido, persona.ci, persona.complemento_ci,
persona.correo, persona.fecha_nac, persona.telefono, persona.telefono2, persona.estado,
pais.nombre AS pais, pais.estado AS estado_pais
FROM persona
INNER JOIN pais ON persona.id_pais=pais.id