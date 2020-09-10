package com.mallchain.wallet.adapter;

import android.content.Context;
import android.text.TextUtils;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.facebook.imagepipeline.request.ImageRequest;
import com.facebook.imagepipeline.request.ImageRequestBuilder;
import com.mallchain.wallet.R;
import com.mallchain.wallet.model.TokentransferBean;
import com.mallchain.wallet.utils.Const;
import com.mallchain.wallet.utils.DateUtil;
import com.mallchain.wallet.utils.Utils;

import java.util.ArrayList;

/**
 * Created by Administrator on 2018/8/4.
 */
public class TokenTransactionAdapter extends BaseAdapter {
    private Context mContext;
    private ArrayList<TokentransferBean> tokenInfoses = new ArrayList<>();
    private String token_symbol;

    public TokenTransactionAdapter(Context mContext, ArrayList<TokentransferBean> tokenInfoses,String token_symbol) {
        this.mContext = mContext;
        if (tokenInfoses != null){
            this.tokenInfoses = tokenInfoses;
        }
        this.token_symbol = token_symbol.toLowerCase();
    }

    @Override
    public int getCount() {
        return tokenInfoses.size();
    }

    @Override
    public Object getItem(int position) {
        return tokenInfoses.size() <= 0 ? position :tokenInfoses.get(position);
    }

    @Override
    public long getItemId(int position) {
        return 0;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        final ViewHolder holder;
        if (convertView != null) {
            holder = (ViewHolder) convertView.getTag();
        } else {
            convertView = View.inflate(mContext, R.layout.item_tokentransaction, null);
            holder = new ViewHolder(convertView);
            holder.tokenImg = (ImageView) convertView.findViewById(R.id.tokenImg);
            holder.walletaddress = (TextView) convertView.findViewById(R.id.walletaddress);
            holder.createTime = (TextView) convertView.findViewById(R.id.createTime);
            holder.amountcount = (TextView) convertView.findViewById(R.id.amountcount);
            convertView.setTag(holder);
        }
        TokentransferBean tokenInfo = tokenInfoses.get(position);
        try {
            holder.createTime.setText(DateUtil.DateDistance(tokenInfo.getTrans_time()));
        }catch (Exception e){
            e.printStackTrace();
        }
        String adress = Const.mFullWallet.getWalletAdress();
        if (adress.contains(tokenInfo.getFrom_addr())){//转出
            holder.walletaddress.setText(tokenInfo.getTo_addr());
            if (TextUtils.isEmpty(tokenInfo.getAmount())){
                holder.amountcount.setText("- ");
            }else{
                holder.amountcount.setText("- "+ Utils.getDecimalFormat(Double.valueOf(tokenInfo.getAmount()))+tokenInfo.getToken_symbol().toLowerCase());
            }

            holder.amountcount.setTextColor(mContext.getResources().getColor(R.color.red));
            holder.tokenImg.setImageResource(R.mipmap.js_images_asset_out);
        }else{
            holder.walletaddress.setText(tokenInfo.getFrom_addr());
            if (TextUtils.isEmpty(tokenInfo.getAmount())){
                holder.amountcount.setText("+ ");
            }else{
                holder.amountcount.setText("+ "+Utils.getDecimalFormat(Double.valueOf(tokenInfo.getAmount()))+tokenInfo.getToken_symbol().toLowerCase());
            }

            holder.amountcount.setTextColor(mContext.getResources().getColor(R.color.color_046EB8));
            holder.tokenImg.setImageResource(R.mipmap.js_images_asset_in);
        }
        return convertView;
    }
    class ViewHolder {
        ImageView tokenImg;
        TextView walletaddress;
        TextView createTime;
        TextView amountcount;

        public ViewHolder(View view) {
        }
    }
}
