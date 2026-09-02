<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PayoutRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PayoutCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PayoutCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Payout::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/payout');
        CRUD::setEntityNameStrings('payout', 'payouts');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
                    protected function setupListOperation()
                    {
                        // Status filter
                        if (request()->filled('status')) {
                            CRUD::addClause('where', 'status', request('status'));
                        }

                        // Merchant filter
                        if (request()->filled('merchant_id')) {
                            CRUD::addClause('where', 'merchant_id', request('merchant_id'));
                        }

                        // Date filter
                        if (request()->filled('date')) {
                            CRUD::addClause('whereDate', 'created_at', request('date'));
                        }

                        CRUD::column('merchant_id')
                            ->label('Merchant');

                        CRUD::column('transaction_id')
                            ->label('Transaction ID');

                        CRUD::column('amount');

                        CRUD::column('status');

                        CRUD::column('created_at')
                            ->label('Date');

                        CRUD::addButtonFromView(
                            'top',
                            'payout_filters',
                            'payout_filters',
                            'beginning'
                        );
                    }
    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PayoutRequest::class);
           CRUD::field('merchant_id')
            ->type('select')
            ->entity('merchant')
            ->model('App\Models\Merchant')
            ->attribute('name');

        CRUD::field('amount')
            ->type('number');

        CRUD::field('status')
            ->type('select_from_array')
            ->options([
                'PENDING' => 'Pending',
                'SUCCESS' => 'Success',
                'FAILED' => 'Failed',
            ]);
            // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
