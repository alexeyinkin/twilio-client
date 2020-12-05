import SortableTable from './SortableTable.js';
import CellTypeEnum from "./CellTypeEnum";

class ProviderTable extends SortableTable {
    static COLUMN_NAME                  = 'name';
    static COLUMN_STATUS                = 'status';
    static COLUMN_LOCATION              = 'location';
    static COLUMN_NOTIFICATION_DATETIME = 'notificationDatetime';
    static COLUMN_BUTTON                = 'button';

    constructor(props) {
        let columns = [
            {name: ProviderTable.COLUMN_NAME,                   title: 'Name',      type: CellTypeEnum.STRING},
            {name: ProviderTable.COLUMN_STATUS,                 title: 'Status',    type: CellTypeEnum.STATUS},
            {name: ProviderTable.COLUMN_LOCATION,               title: 'Location',  type: CellTypeEnum.LOCATION},
            {name: ProviderTable.COLUMN_NOTIFICATION_DATETIME,  title: 'Notified',  type: CellTypeEnum.DATE},
            {name: ProviderTable.COLUMN_BUTTON,                                     type: CellTypeEnum.BUTTON}
        ];

        super(props, columns, 'providerId');
    }

    componentDidMount() {
        this.loadProviders();
    }

    loadProviders() {
        // Uncomment this to test sorting offline.
        // this.setProviders(
        //     [
        //         {
        //             providerId: 1,
        //             name: 'Matthew Dunn',
        //             status: 'PASSED',
        //             location: {building: 1962, street: 'Dagota Avenue'},
        //             notificationDatetime: '2020-10-22 01:09:11'
        //         },
        //         {
        //             providerId: 2,
        //             name: 'Verna Mathis',
        //             status: 'PASSED',
        //             location: {building: 29, street: 'Abwuf Extension'},
        //             notificationDatetime: '2020-09-14 18:38:27'
        //         },
        //         {
        //             providerId: 3,
        //             name: 'Mamie Larson',
        //             status: 'NOT_COMPLETE',
        //             location: {building: 1836, street: 'Agha Road'},
        //             notificationDatetime: '2020-08-19 03:26:07'
        //         }
        //     ]
        // );
        this.props.client.getProviders()
           .then(result => this.setProviders(result.data));
    }

    setProviders(providers) {
        console.log(providers);
        this.setState({rows: providers});
    }

    onButtonClick = (rowId, columnName) => {
        this.props.app.callOrCancel(rowId);
    };

    setCellValue = (providerId, columnName, value) => {
        if (columnName === ProviderTable.COLUMN_STATUS) {
            this.setProviderStatus(providerId, value);
        }
    };

    setProviderStatus(providerId, status) {
        this.props.client.setProviderStatus(providerId, status);
    }
}

export default ProviderTable;
