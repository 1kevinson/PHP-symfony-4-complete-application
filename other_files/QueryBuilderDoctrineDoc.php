<?php
class QueryBuilder
{
    //GREAT EXEMPLE
    // $qb instanceof QueryBuilder

    $qb->select(array('u')) // string 'u' is converted to array internally
    ->from('User', 'u')
    ->where($qb->expr()->orX(
    $qb->expr()->eq('u.id', '?1'),
    $qb->expr()->like('u.nickname', '?2')
    ))
    ->orderBy('u.surname', 'ASC');



    // Example - $qb->select('u')
    // Example - $qb->select(array('u', 'p'))
    // Example - $qb->select($qb->expr()->select('u', 'p'))
    public function select($select = null);

    // addSelect does not override previous calls to select
    //
    // Example - $qb->select('u');
    //              ->addSelect('p.area_code');
    public function addSelect($select = null);

    // Example - $qb->delete('User', 'u')
    public function delete($delete = null, $alias = null);

    // Example - $qb->update('Group', 'g')
    public function update($update = null, $alias = null);

    // Example - $qb->set('u.firstName', $qb->expr()->literal('Arnold'))
    // Example - $qb->set('u.numChilds', 'u.numChilds + ?1')
    // Example - $qb->set('u.numChilds', $qb->expr()->sum('u.numChilds', '?1'))
    public function set($key, $value);

    // Example - $qb->from('Phonenumber', 'p')
    // Example - $qb->from('Phonenumber', 'p', 'p.id')
    public function from($from, $alias, $indexBy = null);

    // Example - $qb->join('u.Group', 'g', Expr\Join::WITH, $qb->expr()->eq('u.status_id', '?1'))
    // Example - $qb->join('u.Group', 'g', 'WITH', 'u.status = ?1')
    // Example - $qb->join('u.Group', 'g', 'WITH', 'u.status = ?1', 'g.id')
    public function join($join, $alias, $conditionType = null, $condition = null, $indexBy = null);

    // Example - $qb->innerJoin('u.Group', 'g', Expr\Join::WITH, $qb->expr()->eq('u.status_id', '?1'))
    // Example - $qb->innerJoin('u.Group', 'g', 'WITH', 'u.status = ?1')
    // Example - $qb->innerJoin('u.Group', 'g', 'WITH', 'u.status = ?1', 'g.id')
    public function innerJoin($join, $alias, $conditionType = null, $condition = null, $indexBy = null);

    // Example - $qb->leftJoin('u.Phonenumbers', 'p', Expr\Join::WITH, $qb->expr()->eq('p.area_code', 55))
    // Example - $qb->leftJoin('u.Phonenumbers', 'p', 'WITH', 'p.area_code = 55')
    // Example - $qb->leftJoin('u.Phonenumbers', 'p', 'WITH', 'p.area_code = 55', 'p.id')
    public function leftJoin($join, $alias, $conditionType = null, $condition = null, $indexBy = null);

    // NOTE: ->where() overrides all previously set conditions
    //
    // Example - $qb->where('u.firstName = ?1', $qb->expr()->eq('u.surname', '?2'))
    // Example - $qb->where($qb->expr()->andX($qb->expr()->eq('u.firstName', '?1'), $qb->expr()->eq('u.surname', '?2')))
    // Example - $qb->where('u.firstName = ?1 AND u.surname = ?2')
    public function where($where);

    // NOTE: ->andWhere() can be used directly, without any ->where() before
    //
    // Example - $qb->andWhere($qb->expr()->orX($qb->expr()->lte('u.age', 40), 'u.numChild = 0'))
    public function andWhere($where);

    // Example - $qb->orWhere($qb->expr()->between('u.id', 1, 10));
    public function orWhere($where);

    // NOTE: -> groupBy() overrides all previously set grouping conditions
    //
    // Example - $qb->groupBy('u.id')
    public function groupBy($groupBy);

    // Example - $qb->addGroupBy('g.name')
    public function addGroupBy($groupBy);

    // NOTE: -> having() overrides all previously set having conditions
    //
    // Example - $qb->having('u.salary >= ?1')
    // Example - $qb->having($qb->expr()->gte('u.salary', '?1'))
    public function having($having);

    // Example - $qb->andHaving($qb->expr()->gt($qb->expr()->count('u.numChild'), 0))
    public function andHaving($having);

    // Example - $qb->orHaving($qb->expr()->lte('g.managerLevel', '100'))
    public function orHaving($having);

    // NOTE: -> orderBy() overrides all previously set ordering conditions
    //
    // Example - $qb->orderBy('u.surname', 'DESC')
    public function orderBy($sort, $order = null);

    // Example - $qb->addOrderBy('u.firstName')
    public function addOrderBy($sort, $order = null); // Default $order = 'ASC'
}