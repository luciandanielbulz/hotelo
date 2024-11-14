<x-layout>

    <div class="container">
        <div class="row">
            <div class="col">
                <h4 class="text-left">Kundenliste - Neues Angebot</h4>
                <p class="text-left">Bitte Kunden auswählen und Angebot erstellen</p>
            </div>
            <div class="col text-right">
                <a href="offers.php?" class="btn btn-transparent">Zurück</a>
            </div>
        </div>

        <div class="row mt-5" id="customerList">
            <div class="col-md-4">
                <form id="searchForm">
                    <div class="form-group">
                        <input type="text" class="form-control" id="searchInput" name="query" placeholder="Suchen">
                    </div>
                </form>
            </div>
            <div class="col text-right">
                <a href="customer_new.php?s=2" class="btn btn-transparent">+ Neuen Kunden</a>
                <!-- Formular für den "Kunde verwenden"-Button -->
                <form id="useCustomerForm" method="post" action="includes/offer_add_new.php" style="display: inline;">
                    <input type="hidden" name="customerid" id="selectedCustomerId" value="">
                    <button type="submit" id="useCustomer" class="btn btn-transparent" disabled>Kunde verwenden</button>
                </form>
            </div>
        </div>

        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Kundennummer</th>
                    <th>Kundenname</th>
                    <th>Adresse</th>
                    <th>PLZ</th>
                    <th>Ort</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $sql = "SELECT *,
                            CASE
                                WHEN TRIM(CompanyName) != '' THEN CompanyName
                                ELSE CustomerName
                            END AS Name
                        FROM Customer
                        WHERE IsSoftDeleted = 0
                        AND ClientId = :clientid
                        ORDER BY Name ASC";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(":clientid", $clientid);
                    $stmt->execute();
                    $result = $stmt->fetchAll();

                    if (!$result) {
                        echo "<tr><td colspan='6'>Keine Einträge gefunden.</td></tr>";
                    } else {
                        foreach ($result as $row) {
                            echo "<tr class='selectable-row' data-customerid='".$row["Id"]."'>";
                            echo "<td class='align-middle'>".$row["Id"]."</td>";
                            echo "<td class='align-middle'>".$row["Name"]."</td>";
                            echo "<td class='align-middle'>".$row["Address"]."</td>";
                            echo "<td class='align-middle'>".$row["PostalCode"]."</td>";
                            echo "<td class='align-middle'>".$row["Location"]."</td>";
                            echo "</tr>";
                        }
                    }
                } catch (PDOException $e) {
                    error_log($e->getMessage());
                    echo "Ein Fehler ist aufgetreten. Bitte versuche es später erneut.";
                }
                ?>
            </tbody>
        </table>
    </div>
</x-layout>
