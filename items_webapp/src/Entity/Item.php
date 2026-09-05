<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 */
#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\Table(name: 'items')]
class Item
{
    /**
     * $id
     *
     * @var integer|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * $name
     *
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * available_stock
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $available_stock = null;

    /**
     * $notes
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    /**
     * $in_assortment
     *
     * @var boolean|null
     */
    #[ORM\Column(nullable: true)]
    private ?bool $in_assortment = null;

    /**
     * $in_stock_out
     *
     * @var boolean|null
     */
    #[ORM\Column(nullable: true)]
    private ?bool $in_stock_out = null;

    /**
     * $price
     *
     * Selling price.
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $price = null;

    /**
     * $total_annual_purchase_cost
     *
     * `C_ACQ`: Total Annual Purchase Cost.
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $total_annual_purchase_cost = null;

    /**
     * $total_annual_cost_of_issuing_orders
     *
     * `CT_E`: Total Annual Cost of Order Issuance.
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $total_annual_cost_of_issuing_orders = null;

    /**
     * $total_annual_cost_of_maintenance_in_stock
     *
     * `CT_M`: Total annual cost of maintaining inventory.
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $total_annual_cost_of_maintenance_in_stock = null;

    /**
     * $annual_demand
     *
     * `D`: Annual, known and non-seasonal demand.
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $annual_demand = null;

    /**
     * $order_issue_cost
     *
     * `C_E`: Order Issuance Cost, considered constant.
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $order_issue_cost = null;

    /**
     * $purchase_price
     *
     * `P`: Purchase or production price.
     * For the moment to be considered independent of the quantity ordered.
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $purchase_price = null;

    /**
     * $annual_interest_rate
     *
     * `i`: Annual Interest Rate, considered constant.
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $annual_interest_rate = null;

    /**
     * $supply_lead_time
     *
     * `L`: Supply Lead Time, if considered null there will be immediate delivery/production.
     *
     * @var integer|null
     */
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $supply_lead_time = null;

    /**
     * $economic_order_quantity
     *
     * `Q`: Economic Order Quantity
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $economic_order_quantity = null;

    /**
     * $description
     *
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * getId
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * getName
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * setName
     *
     * @param string $name
     *
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * getAvailableStock
     *
     * @return string|null
     */
    public function getAvailableStock(): ?string
    {
        return $this->available_stock;
    }

    /**
     * setAvailableStock
     *
     * @param string|null $available_stock
     * @return static
     */
    public function setAvailableStock(?string $available_stock): static
    {
        $this->available_stock = $available_stock;

        return $this;
    }

    /**
     * getPrice
     *
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * setPrice
     *
     * @param string|null $price
     * @return static
     */
    public function setPrice(?string $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * getTotalAnnualPurchaseCost
     *
     * @return string|null
     */
    public function getTotalAnnualPurchaseCost(): ?string
    {
        return $this->total_annual_purchase_cost;
    }

    /**
     * setTotalAnnualPurchaseCost
     *
     * @param string|null $total_annual_purchase_cost
     *
     * @return static
     */
    public function setTotalAnnualPurchaseCost(?string $total_annual_purchase_cost): static
    {
        $this->total_annual_purchase_cost = $total_annual_purchase_cost;

        return $this;
    }

    /**
     * getTotalAnnualCostOfIssuingOrders
     *
     * @return string|null
     */
    public function getTotalAnnualCostOfIssuingOrders(): ?string
    {
        return $this->total_annual_cost_of_issuing_orders;
    }

    /**
     * setTotalAnnualCostOfIssuingOrders
     *
     * @param string|null $total_annual_cost_of_issuing_orders
     *
     * @return static
     */
    public function setTotalAnnualCostOfIssuingOrders(?string $total_annual_cost_of_issuing_orders): static
    {
        $this->total_annual_cost_of_issuing_orders = $total_annual_cost_of_issuing_orders;

        return $this;
    }

    /**
     * getTotalAnnualCostOfMaintenanceInStock
     *
     * @return string|null
     */
    public function getTotalAnnualCostOfMaintenanceInStock(): ?string
    {
        return $this->total_annual_cost_of_maintenance_in_stock;
    }

    /**
     * setTotalAnnualCostOfMaintenanceInStock
     *
     * @param string|null $total_annual_cost_of_maintenance_in_stock
     *
     * @return static
     */
    public function setTotalAnnualCostOfMaintenanceInStock(?string $total_annual_cost_of_maintenance_in_stock): static
    {
        $this->total_annual_cost_of_maintenance_in_stock = $total_annual_cost_of_maintenance_in_stock;

        return $this;
    }

    /**
     * getAnnualDemand
     *
     * @return string|null
     */
    public function getAnnualDemand(): ?string
    {
        return $this->annual_demand;
    }

    /**
     * setAnnualDemand
     *
     * @param string|null $annual_demand
     * @return static
     */
    public function setAnnualDemand(?string $annual_demand): static
    {
        $this->annual_demand = $annual_demand;

        return $this;
    }

    /**
     * getOrderIssueCost
     *
     * @return string|null
     */
    public function getOrderIssueCost(): ?string
    {
        return $this->order_issue_cost;
    }

    /**
     * setOrderIssueCost
     *
     * @param string|null $order_issue_cost
     *
     * @return static
     */
    public function setOrderIssueCost(?string $order_issue_cost): static
    {
        $this->order_issue_cost = $order_issue_cost;

        return $this;
    }

    /**
     * getPurchasePrice
     *
     * @return string|null
     */
    public function getPurchasePrice(): ?string
    {
        return $this->purchase_price;
    }

    /**
     * setPurchasePrice
     *
     * @param string|null $purchase_price
     *
     * @return static
     */
    public function setPurchasePrice(?string $purchase_price): static
    {
        $this->purchase_price = $purchase_price;

        return $this;
    }

    /**
     * getAnnualInterestRate
     *
     * @return string|null
     */
    public function getAnnualInterestRate(): ?string
    {
        return $this->annual_interest_rate;
    }

    /**
     * setAnnualInterestRate
     *
     * @param string|null $annual_interest_rate
     *
     * @return static
     */
    public function setAnnualInterestRate(?string $annual_interest_rate): static
    {
        $this->annual_interest_rate = $annual_interest_rate;

        return $this;
    }

    /**
     * getSupplyLeadTime
     *
     * @return integer|null
     */
    public function getSupplyLeadTime(): ?int
    {
        return $this->supply_lead_time;
    }

    /**
     * setSupplyLeadTime
     *
     * @param integer|null $supply_lead_time
     *
     * @return static
     */
    public function setSupplyLeadTime(?int $supply_lead_time): static
    {
        $this->supply_lead_time = $supply_lead_time;

        return $this;
    }

    /**
     * getEconomicOrderQuantity
     *
     * @return string|null
     */
    public function getEconomicOrderQuantity(): ?string
    {
        return $this->economic_order_quantity;
    }

    /**
     * setEconomicOrderQuantity
     *
     * @param string|null $economic_order_quantity
     *
     * @return static
     */
    public function setEconomicOrderQuantity(?string $economic_order_quantity): static
    {
        $this->economic_order_quantity = $economic_order_quantity;

        return $this;
    }

    /**
     * getDescription
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * setDescription
     *
     * @param string|null $description
     *
     * @return static
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * isInAssortment
     *
     * @return boolean|null
     */
    public function isInAssortment(): ?bool
    {
        return $this->in_assortment;
    }

    /**
     * setInAssortment
     *
     * @param boolean|null $in_assortment
     *
     * @return static
     */
    public function setInAssortment(?bool $in_assortment): static
    {
        $this->in_assortment = $in_assortment;

        return $this;
    }

    /**
     * isInStockOut
     *
     * @return boolean|null
     */
    public function isInStockOut(): ?bool
    {
        return $this->in_stock_out;
    }

    /**
     * setInStockOut
     *
     * @param boolean|null $in_stock_out
     *
     * @return static
     */
    public function setInStockOut(?bool $in_stock_out): static
    {
        $this->in_stock_out = $in_stock_out;

        return $this;
    }

    /**
     * getNotes
     *
     * @return string|null
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * Undocumented function
     *
     * @param string|null $notes
     *
     * @return static
     */
    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }
}
