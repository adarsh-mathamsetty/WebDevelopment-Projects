for $a in doc('auction.xml')/site/open_auctions/open_auction
where $a/bidder/personref/@person = "person3" and $a/bidder/following-sibling::*[personref/@person = "person6"]
return
{
<AuctionItem>
                <reserve>{$a/reserve}</reserve>
</AuctionItem>

}
