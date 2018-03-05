<List-of-Continents>
{
for $x in doc("auction.xml")/site/regions/*
return {
        <Continent name="{name($x)}">
                <Total-items>{count($x/item)}</Total-items>
       </Continent>
        }
}
</List-of-Continents>