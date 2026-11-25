-- =====================================================================
-- CAJA: las compras a proveedores pagadas en efectivo se restan de la caja
-- Correr una sola vez en phpMyAdmin (base "original", pestaña SQL)
-- =====================================================================
ALTER TABLE pedido_compra ADD COLUMN IF NOT EXISTS turno_id INT NULL;
ALTER TABLE pedido_compra ADD INDEX IF NOT EXISTS idx_pedido_compra_turno (turno_id);

-- Si la tabla ya tenía turno_id como NOT NULL con clave foránea a turno_caja:
-- se permite vacío (compras fuera de caja) y, si se borra una caja, sus compras quedan sin caja.
ALTER TABLE pedido_compra DROP FOREIGN KEY pedido_compra_ibfk_3;
ALTER TABLE pedido_compra MODIFY turno_id INT NULL;
ALTER TABLE pedido_compra
    ADD CONSTRAINT pedido_compra_ibfk_3 FOREIGN KEY (turno_id)
    REFERENCES turno_caja (id_turno) ON DELETE SET NULL ON UPDATE CASCADE;
